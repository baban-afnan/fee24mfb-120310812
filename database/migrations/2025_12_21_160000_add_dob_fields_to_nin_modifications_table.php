<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nin_modifications', function (Blueprint $table) {
            // Section 1: Basic Information
            $table->string('first_name', 100)->nullable()->after('description');
            $table->string('surname', 100)->nullable()->after('first_name');
            $table->string('middle_name', 100)->nullable()->after('surname');

            // Section 2: Personal Details
            $table->string('gender', 20)->nullable()->after('middle_name');
            $table->string('marital_status', 30)->nullable()->after('gender');
            $table->date('date_of_birth')->nullable()->after('marital_status');

            // Section 3: Location Information
            $table->string('nationality', 100)->nullable()->after('date_of_birth');
            $table->string('town_of_origin', 100)->nullable()->after('nationality');
            $table->string('town', 100)->nullable()->after('town_of_origin');
            $table->string('city', 100)->nullable()->after('town');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('lga', 100)->nullable()->after('state');

            // Section 4: Origin & Residence
            $table->string('state_of_origin', 100)->nullable()->after('lga');
            $table->string('lga_of_origin', 100)->nullable()->after('state_of_origin');
            $table->text('residence_address')->nullable()->after('lga_of_origin');
            $table->string('phone_number', 20)->nullable()->after('residence_address');

            // Section 5: Additional Details
            $table->string('state_of_birth', 100)->nullable()->after('phone_number');
            $table->string('lga_of_birth', 100)->nullable()->after('state_of_birth');
            $table->string('occupation', 100)->nullable()->after('lga_of_birth');
            $table->text('occupation_address')->nullable()->after('occupation');

            // Section 6: Request Details
            $table->text('request_reason')->nullable()->after('occupation_address');
            $table->string('as_requirement_for', 100)->nullable()->after('request_reason');
            $table->text('others_reason')->nullable()->after('as_requirement_for');
            $table->string('place_of_birth', 100)->nullable()->after('others_reason');
            $table->string('highest_education', 100)->nullable()->after('place_of_birth');

            // Section 7: Father Details
            $table->string('father_surname', 100)->nullable()->after('highest_education');
            $table->string('father_firstname', 100)->nullable()->after('father_surname');
            $table->string('father_middlename', 100)->nullable()->after('father_firstname');
            $table->string('father_town', 100)->nullable()->after('father_middlename');
            $table->string('father_state', 100)->nullable()->after('father_town');
            $table->string('father_lga', 100)->nullable()->after('father_state');

            // Section 7: Mother Details
            $table->string('mother_surname', 100)->nullable()->after('father_lga');
            $table->string('mother_firstname', 100)->nullable()->after('mother_surname');
            $table->string('mother_middlename', 100)->nullable()->after('mother_firstname');
            $table->string('mother_town', 100)->nullable()->after('mother_middlename');
            $table->string('mother_state', 100)->nullable()->after('mother_town');
            $table->string('mother_lga', 100)->nullable()->after('mother_state');

            // Section 8: Registration Centre
            $table->string('registration_state', 100)->nullable()->after('mother_lga');
            $table->string('registration_lga', 100)->nullable()->after('registration_state');
            $table->string('registration_centre', 200)->nullable()->after('registration_lga');

            // Additional fields
            $table->boolean('is_dob_modification')->default(false)->after('registration_centre');
        });

        // Update status enum to include all statuses
        // For MySQL, we need to modify the enum column
        DB::statement("ALTER TABLE nin_modifications MODIFY COLUMN status ENUM('pending', 'in-progress', 'processing', 'query', 'remark', 'resolved', 'successful', 'failed', 'rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('nin_modifications', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'surname', 'middle_name',
                'gender', 'marital_status', 'date_of_birth',
                'nationality', 'town_of_origin', 'town', 'city', 'state', 'lga',
                'state_of_origin', 'lga_of_origin', 'residence_address', 'phone_number',
                'state_of_birth', 'lga_of_birth', 'occupation', 'occupation_address',
                'request_reason', 'as_requirement_for', 'others_reason', 'place_of_birth', 'highest_education',
                'father_surname', 'father_firstname', 'father_middlename', 'father_town', 'father_state', 'father_lga',
                'mother_surname', 'mother_firstname', 'mother_middlename', 'mother_town', 'mother_state', 'mother_lga',
                'registration_state', 'registration_lga', 'registration_centre',
                'service_name', 'modification_field_name', 'is_dob_modification'
            ]);
        });

        // Revert status enum
        DB::statement("ALTER TABLE nin_modifications MODIFY COLUMN status ENUM('pending', 'processing', 'resolved', 'rejected', 'query', 'remark') DEFAULT 'pending'");
    }
};
