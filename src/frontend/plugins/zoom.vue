<template>
  <main></main>
</template>

<script>
import axios from "axios";

import { ZoomMtg } from "zoomus-jssdk";


export default {
  name: "Zoom Module",
  created() {
    this.ZoomMtg.prepapreWebSDK();
    this.ZoomMtg.meetingNumber;
  }
};
methods: {
  this.ZoomMtg.init({
    success: success => {
      this.ZoomMtg.job({
        meetingNumber: this.meetingNumber
      });
    }
  });

	axios({
		method: 'get',
		url: 'https://api.zoom.us/v2/users',
		data: {
			'action: 'get',
		},
		headers: {
			'Authorization': 'Bearer ${token}',
			'User-Agent': 'Zoom-api-Jwt-Request',
			'content-type': 'application/json',
		}
	})

	.then(function(response) {
		console.log(response)
	})
	.catch(function(response) {
		console.log(response)
	})
}
</script>

https://github.com/dvgololobov/zoomusapi
