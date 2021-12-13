<script>
import { Line } from "vue-chartjs";
export default {
  extends: Line,
  data() {
    return {
      // gradient: null,'dfg','try','er','hjh' 35,64,34,75
      cat_name : [],
      cat_data : [],
      gradient2: null,
    };
  },
  mounted() {
  	this.getCatData();
    this.setupStyle();
  },

  methods : {

  	setupStyle(){

    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);

    this.gradient2.addColorStop(0, "rgba(128,0,0, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(128,0,0, 0.25)");
    this.gradient2.addColorStop(1, "rgba(128,0,0, 0)");

  	},
  	generateChart(){
       this.renderChart(
        {
          labels: this.cat_name,
          datasets: [
            {
              label: "Current Stock",
              borderColor: "#05CBE1",
              pointBackgroundColor: "white",
              pointBorderColor: "black",
              borderWidth: 1,
              backgroundColor: this.gradient2,
              data: this.cat_data,
            }
          ]
        },
        { responsive: true, maintainAspectRatio: false }
      );
	  	 
  	},

  	getCatData(){
  		axios.get(base_url+'admin/category-stock/chart')
            .then(response => {
                response.data.map((value,index) => {
                    this.cat_data.push(Number(value.quantity));
                    this.cat_name.push(value.category.category_name);
            });
                this.generateChart();
        });
  	}
  },
}
</script>