// public/app/js/api.js
console.log('api.js cargado');

const API = {
  call: async (endpoint, data = null) => {
    // 1) base absoluta: asume que /api.php está en la raíz del dominio
    const url = `${window.location.origin}/api.php?api=${endpoint}`;

    // 2) prepara opciones de fetch
    const opts = {
      method: data ? 'POST' : 'GET',
      credentials: 'include'
    };
    if (data) {
      opts.headers = { 'Content-Type': 'application/json' };
      opts.body    = JSON.stringify(data);
    }

    console.log('→ FETCH', url, opts);
    const res = await fetch(url, opts);
    return res.json();
  }
};
