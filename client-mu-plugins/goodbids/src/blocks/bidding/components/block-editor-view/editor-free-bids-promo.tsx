import { InfoIcon } from '../icons/info-icon';
import { WaveIcon } from '../icons/wave-icon';

export function EditorFreeBidsPromo() {
	return (
		<div className="flex justify-start flex-col gap-4 bg-contrast-5 rounded p-4">
			<EditorFreeBidsHeading />
			<div className="pl-6 flex flex-col gap-4">
				<EditorFreeBidsContent />
				<EditorFreeBidsInfo />
			</div>
		</div>
	);
}

function EditorFreeBidsInfo() {
	return (
		<div className="flex items-center gap-2">
			<InfoIcon width={16} />
			<a href="#" className="text-sm block">
				Learn more
			</a>
		</div>
	);
}

function EditorFreeBidsHeading() {
	return (
		<div className="flex items-center gap-3">
			<div className="h-6 w-6">
				<WaveIcon />
			</div>
			<p className="m-0">
				<b>Earn free bids:</b>
			</p>
		</div>
	);
}

function EditorFreeBidsContent() {
	return (
		<p className="m-0">
			Place one of the first five <b>paid bids</b> in this auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</p>
	);
}
