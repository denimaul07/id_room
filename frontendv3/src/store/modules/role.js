import store from '../index.js';
/**
 * @param {Array} value
 * @returns {Boolean}
 * @example see @/views/permission/Directive.vue
 */
export default function checkRole(value) {

    if (!value || !Array.isArray(value) || value.length === 0) {
        return false;
    }

    const user = store.getters['auth/currentUser'];

    // Tambahan pengecekan agar tidak error
    if (!user || !user.roles) {
        return false;
    }

    const roles = user.roles;
    const requiredRoles = value;

    const hasRole = roles.some(role => requiredRoles.includes(role.name));
    return hasRole;
    
}