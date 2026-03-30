/** Statamic CP globals available when the addon runs inside the control panel */
export {}

declare global {
    const Statamic: {
        readonly $toast: {
            success: (message: string) => void
            error: (message: string) => void
        }
        readonly $keys: {
            bindGlobal: (
                keys: string | string[],
                handler: (e: KeyboardEvent) => void,
            ) => { destroy: () => void }
        }
        readonly $dirty: {
            add: (name: string) => void
            remove: (name: string) => void
        }
    }
}

/** CP translator — exposed on component instances for templates */
declare module 'vue' {
    interface ComponentCustomProperties {
        __(key: string, replacements?: Record<string, unknown>): string
    }
}
