import type { InjectionKey, Ref } from 'vue'
import type { SitesMap } from './types/localize'

export const siteKey: InjectionKey<Ref<string>> = Symbol('localize-site')
export const sitesKey: InjectionKey<Ref<SitesMap>> = Symbol('localize-sites')
