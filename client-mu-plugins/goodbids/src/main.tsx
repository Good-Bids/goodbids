import 'htmx.org';
import './assets/css/main.css';
import './assets/js/checkout/checkout.js';
import './assets/js/copy-to-clipboard.js';

import { Application } from '@hotwired/stimulus';
import StimulusControllerResolver, {
	createViteGlobResolver,
} from 'stimulus-controller-resolver';

const application = Application.start();

StimulusControllerResolver.install(
	application,
	createViteGlobResolver(import.meta.glob('./**/*_controller.js')),
);
