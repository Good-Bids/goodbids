import PuzzleMan from '../../../assets/images/auction-start.png';
import { ImageProps } from './types';

export function PuzzleManImage(props: ImageProps) {
	return <img alt="" {...props} src={PuzzleMan} />;
}
