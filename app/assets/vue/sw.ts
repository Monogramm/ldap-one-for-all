/* tslint:disable:no-console */

// TODO Replace the static sw.js by this dynamic Typescript Service Worker?

/**
 * good resource if you think you have caching issues:
 * https://gist.github.com/Rich-Harris/fd6c3c73e6e707e312d7c5d7d0f3b2f9
 */

const DEBUG_SW = true;

let assetsToCache: Array<string>;
if (!("serviceWorkerOption" in global)) {
  assetsToCache = [
    // path names are relative to registration.scope
    '/',
    '/sw.js',
    '/manifest.json',
    require('./App')
  ];
} else {
  const { assets } = ((global as any) as any).serviceWorkerOption;
  assetsToCache = [...assets.map((path: string) => pathPrefix + path), '../', '/manifest.json'];
}

const CACHE_NAME: string = Date.now().toString();
const pathPrefix = '';

assetsToCache = assetsToCache.map((path) => {
  return new URL(path, ((global as any) as any).location).toString();
});

// When the service worker is first added to a computer.
self.addEventListener('install', (event: any) => {
  // Perform install steps.
  if (DEBUG_SW) {
    console.log('[SW] Install Event processing');
  }

  // Add core website files to cache during serviceworker installation.
  event.waitUntil(
    (global as any).caches
      .open(CACHE_NAME)
      .then((cache: Cache) => {
        if (DEBUG_SW) {
          console.log(`[SW] Installing cache ${CACHE_NAME}`);
        }
        return cache.addAll(assetsToCache);
      })
      .then(() => {
        if (DEBUG_SW) {
          console.info('[SW] Cached assets: main', assetsToCache);
        }
      })
      .catch((error: any) => {
        console.error(error);
        throw error;
      }),
  );
});

// After the install event.
self.addEventListener('activate', (event: any) => {
  if (DEBUG_SW) {
    console.log(`[SW] Activate event for cache: ${CACHE_NAME}`);
  }

  // Clean the caches
  event.waitUntil(
    (global as any).caches.keys().then((cacheNames: any) => {
      if (DEBUG_SW) {
        console.log('[SW] installed caches:', cacheNames);
      }

      return Promise.all(
        cacheNames.map(
          (cacheName: string): Promise<any> => {
            // Delete the caches that are not the current one.
            if (cacheName !== CACHE_NAME) {
              if (DEBUG_SW) {
                console.info(`[SW] cache deleted: ${cacheName}`);
              }

              return (global as any).caches.delete(cacheName);
            }

            return Promise.resolve();
          },
        ),
      );
    }),
  );
});

self.addEventListener('message', (event: any) => {
  switch (event.data.action) {
  case 'skipWaiting':
    if ((self as any).skipWaiting) {
      (self as any).skipWaiting();
      (self as any).clients.claim();
    }
    break;
  default:
    break;
  }
});

// TODO Respond to 'push' events and trigger notifications on the registration
//self.addEventListener('push', function (event: any) {
//    let title = (event.data && event.data.text());
//    let body = "We have received a push message";
//    let tag = "push-app-notification";
//    let icon = '/build/images/app-logo.png';
//
//    event.waitUntil(
//        self.registration.showNotification(title, { body, icon, tag })
//    );
//});

self.addEventListener('fetch', (event: any) => {
  const request: Request = event.request;

  // Ignore not GET request.
  if (request.method !== 'GET') {
    if (DEBUG_SW) {
      console.log(`[SW] Ignore non GET request ${request.method}`);
    }
    return;
  }

  const requestUrl = new URL(request.url);

  // Ignore difference origin.
  if (requestUrl.origin !== location.origin) {
    if (DEBUG_SW) {
      console.log(`[SW] Ignore difference origin ${requestUrl.origin}`);
    }
    return;
  }

  const resource = (global as any).caches.match(request, { cacheName: CACHE_NAME }).then((cacheResponse: any) => {
    if (cacheResponse) {
      if (DEBUG_SW) {
        console.info(`[SW] fetch URL ${requestUrl.href} from cache`);
      }

      return cacheResponse;
    }

    // Load and cache known assets.
    return fetch(request)
      .then((response: Response) => {
        if (!response || !response.ok) {
          if (DEBUG_SW) {
            console.error(
              `[SW] URL [${requestUrl.toString()}] wrong responseNetwork: ${response.status} ${response.type}`,
            );
          }

          return response;
        }

        if (DEBUG_SW) {
          console.log(`[SW] URL ${requestUrl.href} fetched`);
        }

        const responseCache: Response = response.clone();

        (global as any).caches
          .open(CACHE_NAME)
          .then((cache: Cache) => {
            return cache.put(request, responseCache);
          })
          .then(() => {
            if (DEBUG_SW) {
              console.log(`[SW] Cache asset: ${requestUrl.href}`);
            }
          });

        return response;
      })
      .catch(() => {
        // User is landing on our page.
        if (event.request.mode === 'navigate') {
          return (global as any).caches.match('./');
        }

        return null;
      });
  });

  event.respondWith(resource);
});
