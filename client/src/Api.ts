import React, { useContext, useState, useEffect } from 'react';
import { IAccount } from './Models';

export const AccountContext = React.createContext<IAccount | null>(null);

export function useAccount() {
    const account = useContext(AccountContext);
    if (account) return account;
    throw new Error('Account not in context');
}

async function get<M>(endpoint: string) {
    return fetch(`/${endpoint}`)
        .then(r => r.json() as Promise<M>);
}

export function useSubscribe<M>(endpoint: string) {
    const [result, setResult] = useState<M | undefined>();

    useEffect(() => {
        get<M>(endpoint)
            .then(setResult)
            .catch(() => console.warn(`Could not load ${endpoint}`));
    }, [endpoint]);

    return result;
}

type PostResponse = {
    success: true;
    message?: string;
} | {
    success: false;
    message: string;
}

export async function action(action: string, params: any = {}) {
    return fetch(`/${action}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(params)
    })
        .then(r => r.json() as Promise<PostResponse>)
        .catch(e => ({
            success: false,
            message: 'Faied to submit action',
        }))
        .then(r => r.message);
}