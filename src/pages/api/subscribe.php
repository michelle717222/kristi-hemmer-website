import type { APIRoute } from 'astro';

export const prerender = false;

export const POST: APIRoute = async ({ request }) => {
  let email: string;
  let quizResult: string;

  try {
    ({ email, quizResult } = await request.json());
  } catch {
    return new Response(JSON.stringify({ error: 'Invalid request body' }), {
      status: 400,
      headers: { 'Content-Type': 'application/json' },
    });
  }

  if (!email || !quizResult) {
    return new Response(JSON.stringify({ error: 'Email and quiz result are required' }), {
      status: 400,
      headers: { 'Content-Type': 'application/json' },
    });
  }

  const BREVO_API_KEY = import.meta.env.BREVO_API_KEY;
  const LIST_ID = parseInt(import.meta.env.BREVO_LIST_ID || '5', 10);

  if (!BREVO_API_KEY) {
    console.error('BREVO_API_KEY is not set');
    return new Response(JSON.stringify({ error: 'Server configuration error' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' },
    });
  }

  try {
    const response = await fetch('https://api.brevo.com/v3/contacts', {
      method: 'POST',
      headers: {
        'api-key': BREVO_API_KEY,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        email,
        attributes: {
          QUIZ_RESULT: quizResult,
        },
        listIds: [LIST_ID],
        updateEnabled: true,
      }),
    });

    // Brevo returns 201 (created) or 204 (updated) on success
    if (response.status === 201 || response.status === 204) {
      return new Response(JSON.stringify({ success: true }), {
        status: 200,
        headers: { 'Content-Type': 'application/json' },
      });
    }

    const errorBody = await response.json().catch(() => ({}));
    console.error('Brevo API error:', response.status, errorBody);
    return new Response(JSON.stringify({ error: 'Failed to subscribe. Please try again.' }), {
      status: 502,
      headers: { 'Content-Type': 'application/json' },
    });
  } catch (err) {
    console.error('Brevo fetch error:', err);
    return new Response(JSON.stringify({ error: 'Network error. Please try again.' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' },
    });
  }
};
