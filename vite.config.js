import { defineConfig } from 'vite';
import obfuscator from 'rollup-plugin-obfuscator';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/Notification-Bar.css', 'resources/js/app.js', 'resources/js/admin.js'],
            refresh: true,
            publicDirectory: 'public_html'
        }),
    ],
    //build: {
        //rollupOptions: {
            //output: {
                //plugins: [ // <-- use plugins inside output to not merge chunks on one file
                    //obfuscator({
                        //fileOptions: {
                            //// options
                        //})
                //]
            //}
        //},
        //minify: 'terser',
        //target: 'es2019',
        //terserOptions: {
            //compress: {
                //defaults: false,
            //}
        //}
    //}
});

const obfuscator_options = {
    compact: true,
    debugProtection: false,
};
