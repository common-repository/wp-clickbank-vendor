<form v-on:submit="save()">

<div class="wrap arevico" id="app" v-cloak>
	<h2 class="nav-tab-wrapper">
		<a v-bind:class="{ 'nav-tab-active': tab==1 }" v-on:click.prevent="tab=1" href="#" class="nav-tab" >Pilot</a>
		<a v-bind:class="{ 'nav-tab-active': tab==2 }" v-on:click.prevent="tab=2" href="#" class="nav-tab" >One</a>
		<a v-bind:class="{ 'nav-tab-active': tab==3 }" v-on:click.prevent="tab=3" href="#" class="nav-tab" >50</a>
	</h2>
	<!-- Tab 1 -->
	<div v-cloak v-show="tab==1">
		<i>	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam error possimus aliquid hic consequatur, assumenda eum quos
				libero id debitis reprehenderit voluptate minima odit eaque cum omnis. Voluptatum, cumque odio?
			</i>

		<a href="#" v-on:click="productAccess.push({})">[ add ] </a>
		<div clas="productaccess" v-for="(item, index) in productAccess">
			<input v-if="item.id>=0" type="text" v-model="item.id" />
			<input type="text" v-model="item.email" />
			<input type="text" v-model="item.name" />
			<a href="#" v-on:click="removeItem(item, index)"> delete </a>
		</div>

	</div>

	<!-- Tab 2 -->
	<div v-cloak v-show="tab==2">
		<strong>	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam error possimus aliquid hic consequatur, assumenda eum quos
				libero id debitis reprehenderit voluptate minima odit eaque cum omnis. Voluptatum, cumque odio?
			</strong>
	</div>

	<!-- Tab 3 -->
	<div v-cloak v-show="tab==3">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam error possimus aliquid hic consequatur, assumenda eum quos
			libero id debitis reprehenderit voluptate minima odit eaque cum omnis. Voluptatum, cumque odio?			
	</div>

	<input type="submit" value="submit" >

</div>

</form>


<div>
	<script>

		window.App = new Vue({
			el: '#app',

			data: {
				tab: '1',
				productAccessDeleted:[]
			},

			methods:{
				save: function(){

				}
			}
		})
	</script>
</div>
