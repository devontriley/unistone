import { productsPage } from './productsPage';
import { primaryHeader } from './primaryHeader';
import { Offcanvas } from 'bootstrap/js/src/offcanvas';
import { Gallery } from './gallery';
import USIModal from "./modal";
import USIRegistrationForm from "./register";
import USILoginForm from "./login";
import USIResetPasswordForm from "./resetPassword";
import EditUserProfile from "./editUser";
import ContactMap from './contactMap'
import UserPhotoUploads from './userPhotoUploads'

let usiModalInstance = new USIModal()

if(jQuery('#usiLoginForm').length) {
    let usiLoginFormInstance = new USILoginForm()
}

if(jQuery('#usiRegistrationForm').length) {
    let usiRegistrationFormInstance = new USIRegistrationForm()
}

if(jQuery('#editUserProfileForm').length) {
    let usiEditUserProfileFormInstance = new EditUserProfile()
}

if(jQuery('#usiResetPasswordForm').length) {
    let usiResetPasswordFormInstance = new USIResetPasswordForm()
}

if(jQuery('.contact_map').length) {
    let contactMap = new ContactMap()
}

if(jQuery('#user-photo-uploads').length) {
    let userPhotoUploadsInstance = new UserPhotoUploads()
}