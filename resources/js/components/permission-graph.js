import mermaid from 'mermaid'

let drawn = 0

/**
 * Draws a permission graph with mermaid.js. The Mermaid source stays on the page until the drawing
 * succeeds — and for good when it cannot: the graph is an enhancement, never the only way to read it.
 */
export default function permissionGraph({ source }) {
    return {
        async init() {
            try {
                mermaid.initialize({
                    startOnLoad: false,
                    securityLevel: 'strict',
                    theme: document.documentElement.classList.contains('dark')
                        ? 'dark'
                        : 'default',
                    maxTextSize: 500000,
                    maxEdges: 5000,
                })

                const { svg } = await mermaid.render(
                    `filament-access-control-graph-${++drawn}`,
                    source,
                )

                this.$refs.canvas.innerHTML = svg
                this.$refs.source.hidden = true
            } catch (error) {
                console.warn(
                    'filament-access-control: the permission graph could not be drawn.',
                    error,
                )
            }
        },
    }
}
