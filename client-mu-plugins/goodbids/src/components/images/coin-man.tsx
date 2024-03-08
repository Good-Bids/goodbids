import CoinMan from '../../../assets/images/auction-end.png';
import { ImageProps } from './types';

export function CoinManImage(props: ImageProps) {
	return <img alt="" {...props} src={CoinMan} />;
}
