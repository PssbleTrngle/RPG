import React, { useContext, useState } from 'react';

const fallback = { json: require(`./lang/en.json`), key: '???' };
export const LangContext = React.createContext({
    ...fallback,
    loadLang: (s: string) => { }
});

export function useLang() {
    const [json, setJSON] = useState(fallback.json);
    const [key, setKey] = useState(fallback.key);

    const load = (lang: string) => {
        try {
            const json = require(`./lang/${lang}.json`)
            setJSON(json);
            setKey(lang);
        } catch (ignored) {
            console.error(`Error on loading lang '${lang}'`);
        }
    }

    return [ load, key, json ];
}

export function useLocalization() {
    const { json, loadLang, key } = useContext(LangContext);

    const format = (key: string, ...params: string[]): string => {

        const translation = find(key, json) || find(key, fallback) || key;
        const parsed = translation.replace(/\$([0-9])+/, (_, i) => params[parseInt(i) - 1]);
        return parsed as string;

    }

    return { key, loadLang, format }
}

export function load(lang: string) {
    try {
        const json = require(`./lang/${lang}.json`);
        return json as object;
    } catch (ignored) {
        return false;
    }
}

function find(key: string, object?: any): string | undefined {
    if (!object) return find('', {});

    if (key.match(/.\./)) {
        const [nextKey, ...child] = key.split('.');
        const group: any = object[nextKey];
        return find(child.join('.'), group);
    }

    const value = object[key];
    if (!value || typeof value === 'object')
        return undefined;

    return value.toString();
}