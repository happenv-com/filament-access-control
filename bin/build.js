import * as esbuild from 'esbuild'

/*
 * Builds the package's assets into resources/dist, which is committed and served by Filament as is
 * (see the FilamentAsset registration in the service provider). CI rebuilds and fails when the
 * committed files differ.
 *
 *   npm run build   one minified build
 *   npm run dev     rebuild on change, with inline source maps
 */
const entries = [
    // The permission graph: mermaid.js, loaded only when the graph's modal opens (`x-load`).
    {
        in: 'resources/js/components/permission-graph.js',
        out: 'components/permission-graph',
    },
]

const isDev = process.argv.includes('--dev')

const context = await esbuild.context({
    entryPoints: entries,
    outdir: 'resources/dist',
    bundle: true,
    // An ES module Filament imports on demand; `browser`, because mermaid's dependencies resolve
    // browser builds.
    format: 'esm',
    platform: 'browser',
    target: ['es2020'],
    minify: !isDev,
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    logLevel: 'info',
})

if (isDev) {
    await context.watch()
} else {
    await context.rebuild()
    await context.dispose()
}
