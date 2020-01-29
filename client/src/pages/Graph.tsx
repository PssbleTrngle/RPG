import React, { ReactText, useState, useMemo } from 'react';
import { IEvolution, IClass } from "../Models";
import Graph, { Edge, Node } from 'vis-react'
import { SERVER_URL } from '../Config';
import { useSubscribe } from '../Api';
import { number } from 'prop-types';

class Color {

    constructor(public r: number, public g: number, public b: number) { }

    static mix(...colors: Color[]): Color {

        const a = colors;
        const [r, g, b] = ['r', 'g', 'b'].map(key =>
            a.reduce((t, c: any) => t + c[key] / colors.length, 0)
        );
        return new Color(r, g, b);
    }

    mix(other: Color, by = 0.5): Color {

        const [c1, c2]: any[] = [this, other];
        const [r, g, b] = ['r', 'g', 'b'].map(key =>
            c1[key] * (1 - by) + c2[key] * by
        );

        return new Color(r, g, b);
    }

    static from(hex: string): Color {
        if (hex.startsWith('#')) return Color.from(hex.slice(1));
        if (hex.length === 3) return Color.from(hex.split('').map(c => c + c).join(''));
        if (hex.length !== 6) throw new Error(`Invalid color: ${hex}`);

        const r = Number.parseInt(hex.slice(0, 2), 16);
        const g = Number.parseInt(hex.slice(2, 4), 16);
        const b = Number.parseInt(hex.slice(4, 6), 16);

        return new Color(r, g, b);
    }

    toString() {
        const { r, g, b } = this;
        return `rgb(${r}, ${g}, ${b})`;
    }

}

export function Graphs() {
    const evolutions = useSubscribe<IEvolution[]>('evolution');
    const classes = useSubscribe<IClass[]>('class');
    const [selected, select] = useState<string | undefined>();

    if (!evolutions || !classes) return <p>Loading...</p>;

    const origin = (clazz: IClass): IClass[] => {
        if (clazz.stage === 1) return [clazz];

        return evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => origin(e.from))
            .reduce((t, a) => [...t, ...a], []);
    }

    const starters = classes.filter(c => c.stage === 1);
    const props = { ...{ evolutions, selected, select } }

    return (
        <div className='graphs'>
            <ClassGraph key='total' physics={false} {...{ classes }} {...props} />
            {starters.map(starter => {
                const classesFor = classes.filter(c => !!origin(c).find(o => o.id === starter.id))
                return <ClassGraph key={starter.id} physics={true} classes={classesFor} {...props} />
            })}
        </div>
    )

}

interface GraphProps {
    evolutions: IEvolution[];
    classes: IClass[];
    physics: boolean;
    select: ((node: string) => unknown);
    selected?: ReactText;
}
function ClassGraph(props: GraphProps) {
    const { evolutions, classes, physics, select } = props;

    const options = {
        physics,
        autoResize: true,
        locale: 'en',
        clickToUse: false,
        interaction: {
            keyboard: false,
            dragNodes: true
        },
        nodes: {
            brokenImage: require('./img/missing.png'),
            font: {
                color: '#000',
                size: 20
            },
            color: {
                highlight: {
                    background: '#FFF',
                    border: '#FFF',
                }
            },
        },
        edges: {
            smooth: physics,
            arrows: 'to',
            color: {
                inherit: 'both',
            },
        },
        layout: {
            hierarchical: {
                enabled: false,
                direction: 'UD',
            }
        }
    }

    const starter_colors: { [key: string]: Color } = {
        apprentice: Color.from('#FF0000'),
        traveller: Color.from('#00FF00'),
        wild: Color.from('#0061ff'),
        fighter: Color.from('#FFFF00'),
        psychic: Color.from('#FF8000'),
    };
    const fade = Color.from('#666');
    const max = 4;

    /**
     * Only recalculate if data changes
     */
    const [nodes, edges] = useMemo(() => {

        const count: { [key: number]: number } = {};
        const starts: { [key: number]: number } = {};
        let start = 0;
        for (let i = 1; i <= max; i++) {
            count[i] = classes.filter(n => n.stage === i).length;
            starts[i] = count[i] + start;
            start += count[i];
        }

        const nodes = classes.map(c => ({ id: c.id, label: c.name, color: color(c).toString(), stage: c.stage, deg: deg(c) }))
            .sort(c => c.stage * 1000 + c.deg)
            .map((c, i) => ({ ...c, ...{ deg: (i - starts[c.stage]) / count[c.stage] } }))
        const edges = evolutions.map(e => ({ from: e.from.id, to: e.to.id }));
        return [nodes, edges];

    }, [evolutions, classes].map(a => a.length));

    const color = (clazz: IClass): Color => {
        if (clazz.stage === 1) return starter_colors[clazz.id];

        const colors = evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => color(e.from));

        return Color.mix(...colors).mix(fade, 0.1);
    }

    const deg = (clazz: IClass): number => {
        const starters = Object.keys(starter_colors);
        if (clazz.stage === 1) return starters.indexOf(clazz.id) / starters.length;

        const degrees = evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => deg(e.from));

        if (degrees.length === 1) return degrees[0];

        const [d1, d2] = degrees;
        const d3 = d1 + 1;
        const dist1 = d2 - d1;
        const dist2 = d3 - d2;

        return (dist1 < dist2) ? (d2 - dist1 / 2) : (d3 - dist2 / 2);
    }

    const drawCircles = (context: any) => {
        for (let level = 1; level <= max; level++) {
            const radius = 200 * (level + 1);
            context.beginPath();
            context.arc(0, 0, radius, 0, 2 * Math.PI, false);
            context.fillStyle = level % 2 == 0 ? '#0002' : '#FFF2';
            context.fill();
        }
    }

    return (
        <Graph
            options={options}
            graph={{ nodes: [...nodes], edges: [...edges] }}
            events={{
                click: e => select(e.nodes[0]),
                beforeDrawing: ctx => physics ? null : drawCircles(ctx),
            }}
            getNetwork={net => {
                if (!physics) net.once('initRedraw', () => {

                    nodes.forEach(node => {

                        let radius = 200 * (max - node.stage + 1) + 100;
                        let deg = 2 * Math.PI * node.deg;

                        var x = radius * Math.cos(deg);
                        var y = radius * Math.sin(deg);

                        if (!isNaN(deg))
                            net.moveNode(node.id, x, y);
                    });

                });
            }}
        />
    )

}