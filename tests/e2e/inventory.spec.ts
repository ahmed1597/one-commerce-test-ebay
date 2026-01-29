import { test, expect } from '@playwright/test';

test('connected user sees connected status + can sync', async ({ page }) => {
  await page.route('**/api/ebay/status', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ connected: true, env: 'sandbox' }),
    });
  });

  let inventoryCalls = 0;
  await page.route('**/api/ebay/inventory', async (route) => {
    inventoryCalls++;
    const body =
      inventoryCalls === 1
        ? { data: [] }
        : { data: [{ sku: 'SKU-1', title: 'Test Item', quantity: 3 }] };

    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify(body),
    });
  });

  await page.route('**/api/ebay/sync', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ synced: 1 }),
    });
  });

  await page.goto('/');

  await expect(page.getByTestId('title')).toHaveText('eBay Inventory Sync');
  await expect(page.getByTestId('status')).toContainText('Connected');

  await expect(page.getByTestId('connect-btn')).toHaveCount(0);
  await expect(page.getByTestId('sync-btn')).toBeEnabled();

  await expect(page.getByText('No inventory loaded yet.')).toBeVisible();

  await page.getByTestId('sync-btn').click();

  await expect(page.getByText('SKU-1')).toBeVisible();
  await expect(page.getByText('Test Item')).toBeVisible();
});

test('not connected shows connect and disables sync', async ({ page }) => {
  await page.route('**/api/ebay/status', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ connected: false, env: 'sandbox' }),
    });
  });

  
  await page.route('**/api/ebay/inventory', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ data: [] }),
    });
  });

  await page.goto('/');

  await expect(page.getByTestId('status')).toContainText('Not connected');
  await expect(page.getByTestId('connect-btn')).toBeVisible();
  await expect(page.getByTestId('sync-btn')).toBeDisabled();
});

test('sync shows error message when API returns 409', async ({ page }) => {
  // We force connected=true so Sync button is enabled and clickable.
  await page.route('**/api/ebay/status', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ connected: true }),
    });
  });

  await page.route('**/api/ebay/inventory', async (route) => {
    await route.fulfill({
      status: 200,
      contentType: 'application/json',
      body: JSON.stringify({ data: [] }),
    });
  });

  await page.route('**/api/ebay/sync', async (route) => {
    await route.fulfill({
      status: 409,
      contentType: 'application/json',
      body: JSON.stringify({ message: 'eBay not connected' }),
    });
  });

  await page.goto('/');

  await expect(page.getByTestId('sync-btn')).toBeEnabled();
  await page.getByTestId('sync-btn').click();

  await expect(page.getByTestId('error')).toContainText('eBay not connected');
});
