const webpack = require("webpack");
const path = require("path");
const package = require("./package.json");
const VueLoaderPlugin = require("vue-loader/lib/plugin");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const TerserJSPlugin = require("terser-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const config = require("./config.json");

const devMode = process.env.NODE_ENV !== "production";

// Naming and path settings
var appName = "app";
var entryPoint = {
    frontend: "./src/frontend/main.js",
    admin: "./src/admin/main.js",
    style: "./assets/less/style.less",
    // style: './src/sass/main.scss',
};

var exportPath = path.resolve(__dirname, "./assets/js");

// Enviroment flag
var plugins = [];

// extract css into its own file
plugins.push(
    new MiniCssExtractPlugin({
        filename: "../css/[name].css",
        ignoreOrder: false, // Enable to remove warnings about conflicting order
    })
);

// enable live reload with browser-sync
// set your WordPress site URL in config.json
// file and uncomment the snippet below.
// --------------------------------------
plugins.push(
    new BrowserSyncPlugin({
        proxy: {
            target: config.proxyURL,
        },
        files: ["**/*.php"],
        cors: true,
        reloadDelay: 0,
    })
);

plugins.push(new VueLoaderPlugin());

// Differ settings based on production flag
if (devMode) {
    appName = "[name].js";
} else {
    appName = "[name].min.js";
}

module.exports = {
    entry: entryPoint,
    mode: devMode ? "development" : "production",
    output: {
        path: exportPath,
        filename: appName,
    },

    resolve: {
        alias: {
            vue$: "vue/dist/vue.esm.js",
            "@": path.resolve("./src/"),
            frontend: path.resolve("./src/frontend/"),
            admin: path.resolve("./src/admin/"),
        },
        modules: [
            path.resolve("./node_modules"),
            path.resolve(path.join(__dirname, "src/")),
        ],
    },

    optimization: {
        runtimeChunk: "single",
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /[\\\/]node_modules[\\\/]/,
                    name: "vendors",
                    chunks: "all",
                },
            },
        },
        minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
    },

    plugins,

    module: {
        rules: [{
                test: /\.s(c|a)ss$/,
                use: [
                    "vue-style-loader",
                    "css-loader",
                    {
                        loader: "sass-loader",
                        // Requires sass-loader@^7.0.0
                        options: {
                            implementation: require("sass"),
                            indentedSyntax: true, // optional
                        },
                        // Requires sass-loader@^8.0.0
                        options: {
                            implementation: require("sass"),
                            sassOptions: {
                                indentedSyntax: true, // optional
                            },
                        },
                    },
                ],
            },
            {
                test: /\.vue$/,
                loader: "vue-loader",
            },
            {
                test: /\.js$/,
                use: "babel-loader",
                exclude: /node_modules/,
            },
            {
                test: /\.less$/,
                use: ["vue-style-loader", "css-loader", "less-loader"],
            },
            {
                test: /\.png$/,
                use: [{
                    loader: "url-loader",
                    options: {
                        mimetype: "image/png",
                    },
                }, ],
            },
            {
                test: /\.svg$/,
                use: "file-loader",
            },
            {
                test: /\.(woff(2)?|eot|ttf|otf)(\?[a-z0-9]+)?$/,
                loader: 'url-loader?limit=100000'
            },
            {
                test: /\.css$/,
                use: [{
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: (resourcePath, context) => {
                                return path.relative(path.dirname(resourcePath), context) + "/";
                            },
                            hmr: process.env.NODE_ENV === "development",
                        },
                    },
                    "css-loader",
                ],
            },
        ],
    },
};

// module.exports = env => {
//     webpack.init(env);

//     webpack.chainWebpack(config => {
//         const sassRule = config.module.rule("sass");
//         const sassNormalRule = sassRule.oneOfs.get("normal");
//         // creating a new rule
//         const vuetifyRule = sassRule
//             .oneOf("vuetify")
//             .test(/[\\/]vuetify[\\/]src[\\/]/);
//         // taking all uses from the normal rule and adding them to the new rule
//         Object.keys(sassNormalRule.uses.entries()).forEach(key => {
//             vuetifyRule.uses.set(key, sassNormalRule.uses.get(key));
//         });
//         // moving rule "vuetify" before "normal"
//         sassRule.oneOfs.delete("normal");
//         sassRule.oneOfs.set("normal", sassNormalRule);
//         // adding prefixer to the "vuetify" rule
//         vuetifyRule
//             .use("vuetify")
//             .loader(require.resolve("postcss-loader"))
//             .tap((options = {}) => {
//                 options.sourceMap = process.env.NODE_ENV !== "production";
//                 options.plugins = [
//                     prefixer({
//                         prefix: "[data-vuetify]",
//                         transform(prefix, selector, prefixedSelector) {
//                             let result = prefixedSelector;
//                             if (selector.startsWith("html") || selector.startsWith("body")) {
//                                 result = prefix + selector.substring(4);
//                             }
//                             return result;
//                         }
//                     })
//                 ];
//                 return options;
//             });
//         // moving sass-loader to the end
//         vuetifyRule.uses.delete("sass-loader");
//         vuetifyRule.uses.set("sass-loader", sassNormalRule.uses.get("sass-loader"));
//     });

//     return webpack.resolveConfig();
// };