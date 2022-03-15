<template> </template>

<script>
export default {
	data: {
		apiKey: '8xjZlAPwIpU62U7SQjjS-Q',
		apiURL: 'https://api.convertkit/v3',
	},
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  methods: {
		getTags() {
			const url = api.parseURLRequest(`${this.apiURL}/tags`, {
				api_key: this.apiKey,
			})
			const response = await axios.get(url)
		}
		// axios.post(action, Qs.stringify({
		// 	id: this.convertkidId,

		// })).then((response) => {
		// 	this.subscribed = true
		// }).catch(() => {
		// 	this.error = true,
		// 	this.working = false,
		// }),

	}
</script>

<style></style>
