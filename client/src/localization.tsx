import { ID } from "./models";

namespace Translator {

	let fallback = {
		test: 'Test',
		group: {
			child: 'Child'
		}
	};

	let lang = {...fallback};

	export type Lang = {[key: string]: string | Lang};

	export async function load(lang: string): Promise<boolean> {

		try {
			const json = require(`./lang/${lang}.json`);
			if(json) lang = json;
			return true;
		} catch(_) {
			return false;
		}

	}

	const find = (key: string, object?: Lang): string => {
		if(!object) return find('', {});
	
		if(key.match(/.\./)) {
		const split = key.split('.')
		const [group, child]: [any, string] = [object[split[0]], split[1]];
		return find(child, group);
	}
	
	else return (object[key] || '???').toString();
	
	}

	export const format = (key: string | {id: ID}, ...params: string[]): string => {
		
		if(typeof key === 'string') {

			key = key.toString();
			return find(key, lang);

		} else {
			const model: any = key;
			return format(model.id.toString(), ...params);
		}
	}
}

export default Translator;