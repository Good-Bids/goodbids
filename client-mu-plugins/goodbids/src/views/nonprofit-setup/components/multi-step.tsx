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
			<div className="flex flex-col min-w-80">
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
		'px-4 py-2 text-admin-large border-none text-left hover:bg-admin-secondary hover:text-white focus:text-white transition-all ',
		{
			'bg-admin-main text-white': active,
			'bg-transparent text-admin-main': !active && !fade,
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
		<div className="w-full border-t-8 border-l-8 border-r-6 border-b-6 border-admin-main border-solid p-4 flex flex-col items-center">
			{children}
		</div>
	);
}
