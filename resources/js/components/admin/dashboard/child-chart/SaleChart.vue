<script>
import { Line } from "vue-chartjs";
export default {
  extends: Line,
  data() {
    return {
      // gradient: null,'dfg','try','er','hjh' 35,64,34,75
      pay_date : [],
      pay_amount : [],
      gradient2: null,
    };
  },
  mounted() {
    this.getPaidData();
    this.setupStyle();
  },

  methods : {

    setupStyle(){

    this.gradient2 = this.$refs.canvas
      .getContext("2d")
      .createLinearGradient(0, 0, 0, 450);

    this.gradient2.addColorStop(0, "rgba(111, 66, 193, 0.9)");
    this.gradient2.addColorStop(0.5, "rgba(111, 66, 193, 0.25)");
    this.gradient2.addColorStop(1, "rgba(111, 66, 193, 0)");

    },
    generateChart(){
       this.renderChart(
        {
          labels: this.pay_date,
          datasets: [
            {
              label: "Current Month Sales",
              borderColor: "#05CBE1",
              pointBackgroundColor: "white",
              pointBorderColor: "black",
              borderWidth: 1,
              backgroundColor: this.gradient2,
              data: this.pay_amount,
            }
          ]
        },
        { responsive: true, maintainAspectRatio: false }
      );
       
    },

    getPaidData(){
      axios.get(base_url+'admin/sales-amount/chart')
            .then(response => {
                response.data.map((value,index) => {
                    this.pay_amount.push(Number(value.amount));
                    this.pay_date.push(value.payment_date);
            });
                this.generateChart();
        });
    }
  },
}
</script>