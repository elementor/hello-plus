const { useMemo } = React;

/**
 * The options that received from the API is an array of strings,
 * each string can be a regular value e.g: 'red' or 'blue'
 * or a pair of label and value e.g: 'Red|red' or 'Blue|blue'
 * this parse the array and return an object of 'value' and 'label'
 *
 * @param {Array} options
 */
export default function useFormFieldOptions( options ) {
	return useMemo( () => {
		return options.map( ( rawOption ) => {
			const [ label, value ] = rawOption.split( '|' );

			return {
				label,
				value: value === undefined ? label : value,
			};
		} );
	}, [ options ] );
}
