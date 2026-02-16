/**
 * In dit bestand laden we de HTTP-bibliotheek axios, die wordt gebruikt om verzoeken
 * naar de Laravel back-end te sturen. Axios is geconfigureerd om de CSRF-token
 * automatisch mee te sturen als header voor beveiligde verzoeken.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
