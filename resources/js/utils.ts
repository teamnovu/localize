import type { TranslationScalar, TranslationTree } from './types/localize'

export function inputType(value: unknown): value is TranslationScalar {
    if (value === null) return true
    if (typeof value === 'string') return true
    if (typeof value === 'number') return true
    return false
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
