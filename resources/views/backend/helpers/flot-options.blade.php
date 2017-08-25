<script>
var opts = {
  series: {
    pie: {
      show: true,
      radius: 1,
      innerRadius: 0.5,
      label: {
        show: true,
        radius: 3/4,
        formatter: function(label, series) {
          return "<div style='font-size:11px;color:#222;'>" + Math.round(series.percent) + "%</div>";
        }
      }
    },
  },
  colors: ['#EA6654', '#00567B', '#73BC81', '#8AC6EA', '#FEC70B'],
  grid: {
    hoverable: true,
  },
  tooltip: true,
  tooltipOpts: {
    content: '%s: %y.0 (%p.0%)'
  }
};
</script>
