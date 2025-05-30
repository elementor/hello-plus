module.exports = {
    extends: [
        'plugin:react/recommended',
        'plugin:no-jquery/deprecated',
        'plugin:@wordpress/eslint-plugin/recommended-with-formatting',
		'plugin:import/recommended',
		'plugin:jsx-a11y/strict',
    ],
    plugins: [
        'babel',
        'react',
        'no-jquery',
		'@typescript-eslint',
		'@wordpress',
		'import',
		'react-hooks',
		'prettier',
	],
	ignorePatterns: [ '!**/*' ],
	parser: '@babel/eslint-parser',
    globals: {
        wp: true,
        window: true,
        document: true,
        _: false,
        jQuery: false,
        JSON: false,
        elementorFrontend: true,
        require: true,
        elementor: true,
        DialogsManager: true,
        module: true,
        React: true,
        PropTypes: true,
        __: true,
    },
    parserOptions: {
        ecmaVersion: 2017,
        sourceType: 'module',
        ecmaFeatures: {
            jsx: true,
        },
    },
    rules: {
        // custom canceled rules
        'no-var': 'off',
        'vars-on-top': 'off',
        'wrap-iife': 'off',
        'computed-property-spacing': [ 'error', 'always' ],
        'comma-dangle': [ 'error', 'always-multiline' ],
        'no-undef': 'off',
        'no-unused-vars': [ 'warn', { ignoreRestSiblings: true } ],
        'dot-notation': 'error',
        'no-shadow': 'error',
        'no-lonely-if': 'error',
        'no-mixed-operators': 'error',
        'no-nested-ternary': 'error',
        'no-cond-assign': 'error',
        'space-in-parens': [ 'error', 'always', { exceptions: [ 'empty' ] } ],
        'no-multi-spaces': 'error',
        'semi-spacing': 'error',
        'quote-props': [ 'error', 'as-needed' ],
        indent: [ 'off', 'tab', { SwitchCase: 1 } ],
        'no-mixed-spaces-and-tabs': 'error',
        'padded-blocks': [ 'error', 'never' ],
        'one-var-declaration-per-line': 'error',
        'no-extra-semi': 'error',
        'key-spacing': 'error',
        'array-bracket-spacing': [ 'error', 'always' ],
        'no-else-return': 'error',
        'no-console': 'warn',
        //end of custom canceled rules
        'arrow-parens': [ 'error', 'always' ],
        'arrow-spacing': 'error',
        'brace-style': [ 'error', '1tbs' ],
        camelcase: [ 'error', { properties: 'never' } ],
        'comma-spacing': 'error',
        'comma-style': 'error',
        'eol-last': 'error',
        eqeqeq: 'error',
        'func-call-spacing': 'error',
        'jsx-quotes': 'error',
        'keyword-spacing': 'error',
        'lines-around-comment': 'off',
        'no-bitwise': [ 'error', { allow: [ '^' ] } ],
        'no-caller': 'error',
        'no-debugger': 'warn',
        'no-dupe-args': 'error',
        'no-dupe-keys': 'error',
        'no-duplicate-case': 'error',
        'no-eval': 'error',
        'no-multiple-empty-lines': [ 'error', { max: 1 } ],
        'no-multi-str': 'off',
        'no-negated-in-lhs': 'error',
        'no-redeclare': 'error',
        'no-restricted-syntax': [
            'error',
            {
                selector: 'CallExpression[callee.name=/^__|_n|_x$/]:not([arguments.0.type=/^Literal|BinaryExpression$/])',
                message: 'Translate function arguments must be string literals.',
            },
            {
                selector: 'CallExpression[callee.name=/^_n|_x$/]:not([arguments.1.type=/^Literal|BinaryExpression$/])',
                message: 'Translate function arguments must be string literals.',
            },
            {
                selector: 'CallExpression[callee.name=_nx]:not([arguments.2.type=/^Literal|BinaryExpression$/])',
                message: 'Translate function arguments must be string literals.',
            },
        ],
        'no-trailing-spaces': 'error',
        'no-undef-init': 'error',
        'no-unreachable': 'error',
        'no-unsafe-negation': 'error',
        'no-unused-expressions': 'error',
        'no-useless-return': 'error',
        'no-whitespace-before-property': 'error',
        'object-curly-spacing': [ 'error', 'always' ],
        'prefer-const': 'warn',
        quotes: [ 'error', 'single', { allowTemplateLiterals: true, avoidEscape: true } ],
        semi: 'error',
        'space-before-blocks': [ 'error', 'always' ],
        'space-before-function-paren': [ 'error', {
            anonymous: 'never',
            named: 'never',
            asyncArrow: 'always',
        } ],
        'space-infix-ops': [ 'error', { int32Hint: false } ],
        'space-unary-ops': [ 'error', {
            overrides: {
                '!': true,
                yield: true,
            },
        } ],
        'valid-typeof': 'error',
        yoda: [ 'error', 'always', {
            onlyEquality: true,
        } ],
        'react/react-in-jsx-scope': 'off',
        'babel/semi': 1,
        // 'react/display-name': 'off',
        // 'react/jsx-curly-spacing': [ 'error', {
        // 	when: 'always',
        // 	children: true,
        // } ],
        // 'react/jsx-equals-spacing': 'error',
        // 'react/jsx-indent': [ 'error', 'tab' ],
        // 'react/jsx-indent-props': [ 'error', 'tab' ],
        // 'react/jsx-key': 'error',
        // 'react/jsx-tag-spacing': 'error',
        // 'react/no-children-prop': 'off',
        // 'react/prop-types': 'off',
		'import/no-named-as-default': 'error',
		'react/no-unescaped-entities': 'warn',
    },
	settings: {
		'import/resolver': {
			typescript: {}, // this uses tsconfig.json paths
		},
	},
};
