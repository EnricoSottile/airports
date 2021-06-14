import Airports from './components/Airports/Airports.svelte';

const app = new Airports({
	target: document.body.querySelector("#app"),
});

export default app;