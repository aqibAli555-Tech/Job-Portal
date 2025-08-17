<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 480px; overflow-y: hidden">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="card-title font-weight-bold">Revenue</h4>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-end">
                        <select id="revenueRange"
                                class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius">
                            <option value="1">Last 12 Months</option>
                            <option value="2">Last 2 Years</option>
                            <option value="5">Last 5 Years</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="revenueGraph" class="mt-2" style="height: 300px; width: 100%;"></div>
        </div>
    </div>
</div>
<script>

    $(function () {
        function getCurrentYear() {
            return new Date().getFullYear();
        }

        function generateCategories(years) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const categories = [];
            for (const year of years) {
                for (const month of months) {
                    categories.push(`${month} ${year}`);
                }
            }
            return categories;
        }

        function updateGraph(range) {
            const currentYear = getCurrentYear();
            const years = [];
            for (let i = currentYear - range + 1; i <= currentYear; i++) {
                years.push(i);
            }

            fetch(`{{admin_url('get-revenue')}}/${range}`)
                .then(response => response.json())
                .then(data => {
                    const categories = generateCategories(years);
                    const revenue = [];

                    years.forEach(year => {
                        for (let i = 1; i <= 12; i++) {
                            // Only push the revenue data if it exists (i.e., not null or undefined)
                            if (data[year] && data[year][i] !== undefined) {
                                revenue.push(data[year][i]);
                            } else {
                                // Push null to create a gap in the graph for missing data
                                revenue.push(null);
                            }
                        }
                    });

                    c3.generate({
                        bindto: '#revenueGraph',
                        data: {
                            columns: [
                                ['Revenue', ...revenue]
                            ],
                            types: {
                                Revenue: 'line'
                            }
                        },
                        axis: {
                            x: {
                                type: 'category',
                                categories: categories
                            },
                            y: {
                                tick: {
                                    format: function (d) {
                                        return d.toFixed(2);
                                    }
                                }
                            }
                        }
                    });
                });
        }


        document.getElementById('revenueRange').addEventListener('change', function () {
            const range = parseInt(this.value, 10);
            updateGraph(range);
        });
        updateGraph(1);
    });

</script>
