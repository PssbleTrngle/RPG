import React, { ReactText } from 'react';
import { Component } from "../components/Component";
import { IEvolution, IClass } from "../models";
import Graph, { Edge, Node } from 'vis-react'
import { SERVER_URL } from '../config';
import { number } from 'prop-types';

class Color {

    constructor(public r: number, public g: number, public b: number) { }

    static mix(...colors: Color[]): Color {

        const a: any[] = [...colors];
        const [r, g, b] = ['r', 'g', 'b'].map(key =>
            a.reduce((t, c) => t + c[key] / colors.length, 0)
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
        if (hex.length === 3) return Color.from(hex.split('').map(c => c+c).join(''));
        if(hex.length !== 6) throw new Error(`Invalid color: ${hex}`);

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

export class Graphs extends Component<{}, {evolutions: IEvolution[], classes: IClass[], selected?: ReactText}> {

    constructor(props: any) {
        super(props);
        this.state = { evolutions: [], classes: [] };
    }

    load(resource: string, callback: (result: any) => any) {
        fetch(`${SERVER_URL}/${resource}?max=100`)
            .then(r => r.json())
            .then(callback)
            .catch(e => window.setTimeout(() => this.load(resource, callback), 1000));
    }

    componentDidMount() {
        this.load('evolution', evolutions => this.setState({ evolutions }))
        this.load('class', classes => this.setState({ classes }))
    }

    origin(clazz: IClass): IClass[] {
        const { evolutions } = this.state;
        if (clazz.stage === 1) return [clazz];

        const origins = evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => this.origin(e.from));


        return origins.reduce((array, o) => [...array, ...o], []);
    }

    select(node?: ReactText) {
        console.log(`Selected ${node}`)
        this.setState({ selected: node });
    }

    template() {
        const { evolutions, classes, selected } = this.state;

        if(!evolutions.length || !classes.length) return <p>Loading...</p>;

        const starters = classes.filter(c => c.stage === 1);
        const props = {...{ evolutions, selected, select: (n: ReactText) => this.select(n) }}

        return (
            <div className='graphs'>
                <ClassGraph key='total' options={{ physics: false }} {...{ classes }} {...props} />
                {starters.map(starter => {
                    const classesFor = classes.filter(c => !!this.origin(c).find(o => o.id === starter.id))
                    return <ClassGraph key={starter.id} options={{ physics: true }} classes={classesFor} {...props} />
                })}
            </div>
        )
    }

}

interface GraphProps {
    evolutions: IEvolution[];
    classes: IClass[];
    options: { physics: boolean };
    select: ((n: ReactText) => any);
    selected?: ReactText;
}
class ClassGraph extends Component<GraphProps, {nodes: (Node & {stage: number, deg: number})[], edges: Edge[]}> {

    static starter_colors: { [key: string]: Color } = {
        apprentice: Color.from('#FF0000'),
        traveller: Color.from('#00FF00'),
        wild: Color.from('#0061ff'),
        fighter: Color.from('#FFFF00'),
        psychic: Color.from('#FF8000'),
    };
    static fade = Color.from('#666');

    static max = 4;

    constructor(props: GraphProps) {
        super(props);

        const { evolutions, classes } = props;

        const count: {[key: number]: number} = {};
        const starts: {[key: number]: number} = {};
        let start = 0;
        for(let i = 1; i <= ClassGraph.max; i++) {
            count[i] = classes.filter(n => n.stage === i).length;
            starts[i] = count[i] + start;
            start += count[i];
        }
        
        const nodes = classes.map(c => ({ id: c.id, label: c.name, color: this.color(c).toString(), stage: c.stage, deg: this.deg(c) }))
                            .sort(c => c.stage * 1000 + c.deg)
                            .map((c, i) => ({...c, ...{ deg: (i - starts[c.stage]) / count[c.stage] }}))
        const edges = evolutions.map(e => ({ from: e.from.id, to: e.to.id }));

        this.state = { nodes, edges };
    }

    options() {
        const { physics } = this.props.options;
        return {
            autoResize: true,
            physics,
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
    }

    color(clazz: IClass): Color {
        if (clazz.stage === 1) return ClassGraph.starter_colors[clazz.id];
        const { evolutions } = this.props;

        const colors = evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => this.color(e.from));

        return Color.mix(...colors).mix(ClassGraph.fade, 0.1);
    }

    deg(clazz: IClass): number {
        const starters = Object.keys(ClassGraph.starter_colors);
        if (clazz.stage === 1) return starters.indexOf(clazz.id.toString()) / starters.length;
        const { evolutions } = this.props;

        const degrees = evolutions
            .filter(e => e.to.id === clazz.id)
            .map(e => this.deg(e.from));

        if(degrees.length === 1) return degrees[0];

        const [ d1, d2 ] = degrees;
        const d3 = d1 + 1;
        const dist1 = d2 - d1;
        const dist2 = d3 - d2;
        
    	return (dist1 < dist2) ? (d2 - dist1 / 2) : (d3 - dist2 / 2);
    }

    drawCircles(context: any) {
        for(let level = 1; level <= ClassGraph.max; level++) {
            const radius = 200 * (level + 1);
            context.beginPath();
            context.arc(0, 0, radius, 0, 2 * Math.PI, false);
            context.fillStyle = level % 2 == 0 ? '#0002' : '#FFF2';
            context.fill();
        }
    }

    template() {
        const { select, options } = this.props;
        const { edges, nodes } = this.state;
        const { physics } = options;

        return (
            <Graph
                options={this.options()}
                graph={{ nodes: [...nodes], edges: [...edges] }}
                events={{
                    click: e => select(e.nodes[0]),
                    beforeDrawing: ctx => physics ? null : this.drawCircles(ctx),
                }}
                getNetwork={net => {
                    if(!physics) net.once('initRedraw', () => {
        
                        nodes.forEach(node => {
        
                            let radius = 200 * (ClassGraph.max - node.stage + 1) + 100;
                            let deg = 2 * Math.PI * node.deg;
        
                            var x = radius * Math.cos(deg);
                            var y = radius * Math.sin(deg);

                            if(!isNaN(deg))
                                net.moveNode(node.id, x, y);
                        });
        
                    });
                }}
            />
        )
    }

}