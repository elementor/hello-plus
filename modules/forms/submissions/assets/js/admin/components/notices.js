import { useNoticesContext } from '../context/notices-context';
import Notice from './notice';

export default function Notices() {
	const { notices, dismiss } = useNoticesContext();

	return notices.map( ( notice ) => (
		<Notice
			key={ notice.key }
			model={ notice }
			dismiss={ () => dismiss( notice.key ) }
		/>
	) );
}
