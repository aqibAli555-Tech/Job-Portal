<div id="addSkill" class="modal fade" tabindex="-1" role="dialog"aria-labelledby="addSkillLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Enter Skill and Skill Image</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body">
                <form action="{{ admin_url('/employeeSkill/skillAdd') }}" method="post"
                enctype="multipart/form-data">
                <!-- Skill input -->
                    <div class="form-group">
                        <label for="skillInput">Skill:</label>
                        <input type="text" class="form-control" id="skillInput" name="skill"
                        placeholder="Enter skill">
                    </div>

                    <!-- Image input -->
                    <div class="form-group pt-4">
                        <label for="imageInput">Skill Image:</label>
                        <input type="file" class="form-control-file" name="image" id="imageInput">
                    </div>

                    <!-- Display skill and image -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>