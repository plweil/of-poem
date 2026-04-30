// Suppress IDE warnings
// noinspection JSUnresolvedVariable,JSUnusedLocalSymbols
// noinspection JSUnusedGlobalSymbols

const { createApp, ref } = Vue;

// Define the featured poem component
const FeaturedPoem = {
	setup() {
		/**
		 * @type {{ title: string, author: { first_name: string, last_name: string }, notes: string, html: string }}
		 */
		const poem = ref(window.FEATURED_POEM || {
			title: '',
			author: { first_name: '', last_name: '' },
			notes: '',
			html: ''
		});

		return { poem };
	},
	template: `
    <div v-if="poem">
      <h1 v-if="poem.title">{{ poem.title }}</h1>
      <p v-if="poem.author">{{ poem.author.first_name }} {{ poem.author.last_name }}</p>
      <div v-html="poem.html"></div>
      <p v-if="poem.notes"><em>{{ poem.notes }}</em></p>
    </div>
    <div v-else>
      <p>No featured poem available.</p>
    </div>
  `
};

// Create Vue app and register the component
const app = createApp({});
app.component('featured-poem', FeaturedPoem);

// Mount the app to the container
app.mount('#app');
