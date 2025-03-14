<?php
// backend/controllers/AdminController.php
/*
require_once 'models/User.php'; // User model for jobseeker and company management
require_once 'models/Job.php'; // Job model for job management

class AdminController {

    // Display the admin dashboard
    public function dashboard() {
        include_once 'frontend/admin/dashboard.php'; // Dashboard view for admin
    }

    // List all jobseekers
    public function listJobseekers() {
        $jobseekers = User::getAllByRole('jobseeker');
        include_once 'frontend/admin/jobseekers_list.php';
    }

    // List all companies
    public function listCompanies() {
        $companies = User::getAllByRole('company');
        include_once 'frontend/admin/companies_list.php';
    }

    // List all job postings
    public function listJobs() {
        $jobs = Job::getAll();
        include_once 'frontend/admin/jobs_list.php';
    }

    // Edit jobseeker details
    public function editJobseeker($id) {
        $jobseeker = User::findByIdAndRole($id, 'jobseeker');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jobseeker->name = trim($_POST['name']);
            $jobseeker->email = trim($_POST['email']);
            if ($jobseeker->save()) {
                header("Location: /backend/index.php?controller=admin&action=listJobseekers");
            } else {
                echo "Error updating jobseeker.";
            }
        } else {
            include_once 'frontend/admin/edit_jobseeker.php';
        }
    }

    // Edit company details
    public function editCompany($id) {
        $company = User::findByIdAndRole($id, 'company');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $company->name = trim($_POST['name']);
            $company->email = trim($_POST['email']);
            $company->company_name = trim($_POST['company_name']);
            $company->company_address = trim($_POST['company_address']);
            if ($company->save()) {
                header("Location: /backend/index.php?controller=admin&action=listCompanies");
            } else {
                echo "Error updating company.";
            }
        } else {
            include_once 'frontend/admin/edit_company.php';
        }
    }

    // Delete a jobseeker
    public function deleteJobseeker($id) {
        if (User::deleteByIdAndRole($id, 'jobseeker')) {
            header("Location: /backend/index.php?controller=admin&action=listJobseekers");
        } else {
            echo "Error deleting jobseeker.";
        }
    }

    // Delete a company
    public function deleteCompany($id) {
        if (User::deleteByIdAndRole($id, 'company')) {
            header("Location: /backend/index.php?controller=admin&action=listCompanies");
        } else {
            echo "Error deleting company.";
        }
    }

    // Delete a job posting
    public function deleteJob($id) {
        if (Job::deleteById($id)) {
            header("Location: /backend/index.php?controller=admin&action=listJobs");
        } else {
            echo "Error deleting job.";
        }
    }
} */
?>
