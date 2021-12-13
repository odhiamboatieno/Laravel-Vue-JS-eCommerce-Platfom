<template>
<div class="row">
	<div class="pagination-section col-md-12 clearfix text-center" v-if="pageData.last_page > 1">
	    <div class="pagination-main ">
	        <ul class="pagination mt20">
	        	<li :class="[ ((pageData.current_page == 1) ? 'disabled' : '') ]">
				<a
					:href="'?page='+pageData.current_page"
					@click.prevent="pageClicked(pageData.current_page-1)"
					aria-label="Previous"
					v-if="pageData.current_page != 1"
					>
					<i class='lni lni-chevron-left'></i>
				</a>
				<a v-else>
					<i class='lni lni-chevron-left'></i>
				</a>
			</li>
	        <li v-for="pageNo in range(paginateLoop, numberOfPage)"
				:class="[ ((pageData.current_page == pageNo) ? 'active' : '') ]"
				>
				<a :href="'?page='+pageNo" @click.prevent="pageClicked(pageNo)">{{ pageNo }}</a>
			</li>
	        <li :class="[ ((pageData.current_page == pageData.last_page) ? 'disabled' : '') ]">
				<a
					:href="'?page='+pageData.current_page"
					@click.prevent="pageClicked(pageData.current_page+1)"
					aria-label="Next"
					v-if="pageData.current_page != pageData.last_page"
					>
					<i class='lni lni-chevron-right'></i>
				</a>
				<a v-else>
					<i class='lni lni-chevron-right'></i>
				</a>
			</li>
	        </ul>
	    </div>
	</div>
</div>
</template>
<script type="text/javascript">
    import {EventBus} from '../../../vue-assets';

	export default{

		props : ['pageData'],
		data(){

			return {
			}
		},

		methods : {
			range(start, count) {
				return Array.apply(0, Array(count)).map(function(element, index) {
					return index + start;
				});
			},

			pageClicked(page){
                
                this.$parent.pageClicked(page);

			}
		},

			computed: {
			paginateLoop() {
				let pageData = this.pageData;
				if (pageData.last_page > 11) {
					if (pageData.last_page - 5 <= pageData.current_page) {
						return pageData.last_page - 10;
					}
					if (pageData.current_page > 6) {
						return pageData.current_page - 5;
					}
				}
				return 1;
			},
			numberOfPage() {
				if (this.pageData.last_page < 11) {
					return this.pageData.last_page;
				} else {
					return 11;
				}
			}
		}
	}


</script>

<style scoped>
	.disabled {
		cursor: not-allowed;
	}

	.active a{

		background-color: #000000db !important;
		color: #fff !important;
	}
</style>