import GoodbidsLogo from '../../../assets/images/Goodbids-Logo-green.svg';
import { ImageProps } from './types';

export function Logo(props: ImageProps) {
	return <img alt="Goodbids Logo" {...props} src={GoodbidsLogo} />;
}
