<h2 class="text-center text-primary mt-3"><?php echo $personal_data_label ?></h2>
    <form class="row g-3 mt-3" method="POST" enctype="multipart/form-data">

    <div class="col-md-4 mt-2"></div>

    <div class="col-md-4 mt-2">
    
        <figure class="mt-4 text-center">

            <img class="img-thumbnail text-center profile-picture" id="profile_picture" alt="Profile Picture" src="<?php echo  $user_photo. "?" .time() ?>"/>
        
        </figure>
    
    </div>

    <div class="col-md-4 mt-2"></div>

    <div class="col-md-5 mt-4">

        <label for="input_name" class="form-label"><?php echo $name_label ?></label>
        
        <input type="text" class="form-control" id="input_name" name="name" value="<?php echo $user->get_name() ?>" placeholder="<?php echo $name_placeholder ?>" required>

    </div>

    <div class="col-md-2 mt-0"></div>

    <div class="col-md-5 mt-4">

        <label for="select_gender" class="form-label"><?php echo $gender_label ?></label>

        <select class="form-select" id="select_gender" name="gender"  aria-label="Genders" required>

            <option value="F" <?php echo ($user->get_gender_code() == "F" ? "selected" : "") ?>>Feminino</option>
            <option value="M" <?php echo ($user->get_gender_code() == "M" ? "selected" : "") ?>>Masculino</option>
            <option value="NB" <?php echo ($user->get_gender_code() == "NB" ? "selected" : "") ?>>Não Binário</option>

        </select>

    </div>

    <div class="col-md-5 mt-4">

        <label for="input_birthdate" class="form-label"><?php echo $birthdate_label ?></label>

        <input type="date" class="form-control id="input_birthdate" name="birthdate"  value="<?php echo $user->get_birthdate() ?>" >


    </div>

    <div class="col-md-2  mt-0"></div>

    <div class="col-md-5 mt-4">

        <label for="file_photo" class="form-label"><?php echo $photo_label ?></label>
        

        <input type="file" class="form-control" id="file_photo" name="photo" accept="image/*"/>

    </div>

    <div class="col-md-8 col-lg-10"></div>

    <div class="d-grid gap-2 col-12 col-sm-9 col-md-4 col-lg-2 mx-auto mt-4 mb-4">

        <button class="btn btn-primary" type="submit" name="update"><?php echo  $update_label ?></button>

    </div>

</form>