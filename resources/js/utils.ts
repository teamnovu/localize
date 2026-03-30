import type { SitesMap, TranslationScalar, TranslationTree } from './types/localize'

export function inputType(value: unknown): value is TranslationScalar {
    if (value === null) return true
    if (typeof value === 'string') return true
    return false
}

/** Recursively coerce numeric JSON leaves to strings (translation content is string-only). */
export function sanitizeTranslationTree(tree: TranslationTree): TranslationTree {
    const out: TranslationTree = {}
    for (const [key, value] of Object.entries(tree)) {
        if (value === null || typeof value === 'string') {
            out[key] = value
        } else if (typeof value === 'number') {
            out[key] = String(value)
        } else if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
            out[key] = sanitizeTranslationTree(value as TranslationTree)
        }
    }
    return out
}

export function sanitizeSitesMap(sites: SitesMap): SitesMap {
    const out: SitesMap = {}
    for (const [handle, site] of Object.entries(sites)) {
        out[handle] = {
            ...site,
            translations: sanitizeTranslationTree(site.translations),
        }
    }
    return out
}

export function deslug(string: string | number = ''): string | number {
    if (typeof string !== 'string') {
        return string
    }

    return string
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (l) => l.toUpperCase())
        .replace(/([a-z])([A-Z])/g, '$1 $2')
}

export function walkObject(
    object: TranslationTree | undefined,
    path: string[],
    name: string,
): TranslationScalar | undefined {
    let sub: TranslationTree | TranslationScalar | undefined = object
    for (const step of path) {
        if (sub === undefined || sub === null || typeof sub !== 'object') {
            return undefined
        }
        sub = sub[step] as TranslationTree | TranslationScalar | undefined
    }
    if (sub === undefined || sub === null || typeof sub !== 'object') {
        return undefined
    }
    return sub[name] as TranslationScalar | undefined
}

export function readXsrfToken(): string | null {
    const row = document.cookie.split('; ').find((r) => r.startsWith('XSRF-TOKEN='))
    if (!row) return null
    return decodeURIComponent(row.slice('XSRF-TOKEN='.length))
}
