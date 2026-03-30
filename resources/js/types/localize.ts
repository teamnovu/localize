/** Leaf values stored in translation JSON */
export type TranslationScalar = string | number | null

/** Nested key/value translation structure */
export type TranslationTree = {
    [key: string]: TranslationScalar | TranslationTree
}

/** Site metadata + translations payload from Statamic */
export interface SiteConfig {
    handle: string
    name: string
    translations: TranslationTree
}

/** Map of site handle → site config (as provided by the CP) */
export type SitesMap = Record<string, SiteConfig>

/** Successful save response body from the localize action */
export interface LocalizeSaveResponse {
    status: string
    sites: SitesMap
}

/** Laravel validation error JSON */
export interface LaravelValidationErrorBody {
    message: string
    errors?: Record<string, string[] | string>
}

export function isValidationError(data: unknown): data is LaravelValidationErrorBody {
    return (
        typeof data === 'object' &&
        data !== null &&
        'message' in data &&
        typeof (data as LaravelValidationErrorBody).message === 'string'
    )
}

export function isSaveSuccess(data: unknown): data is LocalizeSaveResponse {
    return (
        typeof data === 'object' &&
        data !== null &&
        'status' in data &&
        'sites' in data &&
        typeof (data as LocalizeSaveResponse).status === 'string'
    )
}
