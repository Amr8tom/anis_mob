import http from 'k6/http';
import { check } from 'k6';

export const options = {
  scenarios: {
    check_in: {
      executor: 'constant-arrival-rate',
      rate: Number(__ENV.RATE || 100),
      timeUnit: '1s',
      duration: '2m',
      preAllocatedVUs: 100,
      maxVUs: 1000,
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.001'],
    http_req_duration: ['p(95)<300'],
  },
};

const baseUrl = __ENV.BASE_URL || 'http://127.0.0.1:8000';

export default function () {
  const token = __ENV.API_TOKEN;
  const qr = __ENV.QR_TOKEN;
  const idempotencyKey = `load-${__VU}-${__ITER}`;
  const response = http.post(
    `${baseUrl}/api/v1/workspace-visits/check-in`,
    JSON.stringify({ qr_payload: qr }),
    {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Idempotency-Key': idempotencyKey,
      },
    },
  );
  check(response, { 'expected response': (result) => [201, 409].includes(result.status) });
}
