import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  scenarios: {
    public_catalog: {
      executor: 'ramping-arrival-rate',
      startRate: 20,
      timeUnit: '1s',
      preAllocatedVUs: 100,
      maxVUs: 1000,
      stages: [
        { target: 100, duration: '1m' },
        { target: 500, duration: '3m' },
        { target: 1000, duration: '2m' },
      ],
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.001'],
    http_req_duration: ['p(95)<300', 'p(99)<800'],
  },
};

const baseUrl = __ENV.BASE_URL || 'http://127.0.0.1:8000';

export default function () {
  const response = http.get(`${baseUrl}/api/v1/workspaces?filter=nearby&latitude=30.0444&longitude=31.2357&per_page=20`);
  check(response, {
    'status is 200': (result) => result.status === 200,
    'request id exists': (result) => Boolean(result.headers['X-Request-Id']),
  });
  sleep(0.1);
}
