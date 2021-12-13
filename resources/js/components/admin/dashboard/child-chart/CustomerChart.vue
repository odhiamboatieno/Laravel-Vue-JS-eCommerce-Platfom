<script>
import { Line } from "vue-chartjs";
export default {
  extends: Line,
  data() {
    return {
      // gradient: null,
      customer_name : [],
      customer_data : [],
      gradient2: null,
    };
  },
  mounted() {
    this.getCustomerData();
    this.setupStyle();
    console.log(this.customer_data)
              console.log(this.customer_name)
  },

  methods : {

  	setupStyle(){

    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);

    this.gradient2.addColorStop(0, "rgba(0, 231, 255, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(0, 231, 255, 0.25)");
    this.gradient2.addColorStop(1, "rgba(69, 111, 1, 0)");

  	},
  	generateChart(){
  	 this.renderChart(
      {
        labels: this.customer_name,
        datasets: [
          {
            label: "Customer",
            borderColor: "#05CBE1",
            pointBackgroundColor: "white",
            pointBorderColor: "black",
            borderWidth: 1,
            backgroundColor: this.gradient2,
            data: this.customer_data,
          }
        ]
      },
      { responsive: true, maintainAspectRatio: false }
    );
  	},

  	getCustomerData(){
  		axios.get(base_url+'admin/last-month/customer/chart')
            .then(response => {
                response.data.map((value,index) => {
                  this.customer_data.push(Number(value.total));
                  this.customer_name.push(value.date);
            });
              this.generateChart();
        });
  	}
  },
}
</script>