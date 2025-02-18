import clsx from 'clsx';
import { useState } from 'react';

type StepType = {
	label: string;
	component: React.ReactNode;
	fade?: boolean;
};

type MultiStepProps = {
	defaultStep: string;
	steps: Record<string, StepType>;
};

export function MultiStep({ defaultStep, steps }: MultiStepProps) {
	const [step, setStep] = useState(defaultStep);

	return (
		<div className="flex">
			<div className="flex min-w-80 flex-col">
				{Object.entries(steps).map(([key, value]) => (
					<Button
						key={key}
						onClick={() => setStep(key)}
						active={step === key}
						fade={value.fade}
					>
						{value.label}
					</Button>
				))}
			</div>

			<Container>{steps[step].component}</Container>
		</div>
	);
}

type ButtonProps = Exclude<
	React.ButtonHTMLAttributes<HTMLButtonElement>,
	'className'
> & {
	active: boolean;
	fade?: boolean;
};

function Button(props: ButtonProps) {
	const { active, fade = false, ...rest } = props;

	const classes = clsx(
		'border-none px-4 py-2 text-left text-gb-lg transition-all hover:bg-gb-green-500 hover:text-white focus:underline',
		{
			'bg-gb-green-700 text-white': active,
			'bg-transparent text-gb-green-700': !active && !fade,
			'bg-transparent text-black/50': !active && fade,
		},
	);

	return <button {...rest} className={classes} />;
}

type ContainerProps = {
	children: React.ReactNode;
};

function Container({ children }: ContainerProps) {
	return (
		<div className="flex w-full flex-col items-center border-b-6 border-l-8 border-r-6 border-t-8 border-solid border-gb-green-700 p-4">
			{children}
		</div>
	);
}
