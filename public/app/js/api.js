// public/app/js/api.js
console.log('api.js cargado');

const API = {
  call: async (endpoint, data = null, method = null) => {
    let url = `${window.location.origin}/api.php?api=${endpoint}`;

    // Si se especifica un método, se usa. Si no, se infiere (POST si hay datos, si no GET).
    const finalMethod = method || (data ? 'POST' : 'GET');

    const opts = {
      method: finalMethod,
      credentials: 'include'
    };

    if (data) {
      // Si es POST, los datos van en el body.
      if (finalMethod === 'POST') {
        opts.headers = { 'Content-Type': 'application/json' };
        opts.body    = JSON.stringify(data);
      } else { 
        // Si es GET, los datos van como parámetros en la URL.
        url += '&' + new URLSearchParams(data).toString();
      }
    }

    console.log('→ FETCH', url, opts);
    const res = await fetch(url, opts);
    return res.json();
  }
};
