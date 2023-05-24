module.exports = {
    env: {
        node: true,
        'vue/setup-compiler-macros': true,
    },
    extends: [
        'plugin:vue/base',
        'eslint:recommended',
        'plugin:vue/vue3-recommended',
        'plugin:tailwindcss/recommended',
    ],
    rules: {
        semi: ['error', 'never'],
        quotes: ['error', 'single'],
        indent: ['error', 2],
        'block-spacing': ['error', 'always'],
        'keyword-spacing': ['error'],
        'padding-line-between-statements': [ 'error', {
            blankLine: 'always', prev: '*', next: ['return', 'block-like'],
        }],
        'space-before-blocks': ['error', 'always'],
        'eol-last': ['error', 'always'],
        'vue/multi-word-component-names': 0,
        'vue/first-attribute-linebreak': 0,
        'vue/max-attributes-per-line': 0,
        'vue/html-indent': ['error', 2],
        'comma-dangle': ['error', 'always-multiline'],
        'comma-spacing': ['error'],
        'key-spacing': ['error'],
        'object-curly-spacing': ['error', 'always'],
        'tailwindcss/no-custom-classname': 0,
        'vue/require-default-prop': 0,
        'vue/padding-line-between-blocks': ['error', 'always'],
        'vue/component-tags-order': ['error', {
            'order': ['script', 'template', 'style'],
        }],
    },
}
