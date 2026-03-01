import { existsSync, readFileSync } from 'node:fs';
import { execSync } from 'node:child_process';

const packageJsonPath = 'node_modules/tailwindcss/package.json';
const desiredRange = '^4.2.1';
const installCommand = `npm i -D --no-audit --no-fund tailwindcss@${desiredRange}`;

const readInstalledMajor = () => {
  if (!existsSync(packageJsonPath)) {
    return null;
  }

  try {
    const pkg = JSON.parse(readFileSync(packageJsonPath, 'utf8'));
    return Number.parseInt(String(pkg.version || '').split('.')[0], 10);
  } catch {
    return null;
  }
};

const installedMajor = readInstalledMajor();

if (installedMajor === 4) {
  console.log('[tailwind-repair] tailwindcss v4 is already present.');
  process.exit(0);
}

if (installedMajor && installedMajor !== 4) {
  console.log(`[tailwind-repair] Detected tailwindcss v${installedMajor}. Upgrading to v4...`);
} else {
  console.log('[tailwind-repair] tailwindcss is missing. Installing v4...');
}

try {
  execSync(installCommand, {
    stdio: 'inherit',
    timeout: 20_000,
  });

  if (readInstalledMajor() === 4) {
    console.log('[tailwind-repair] tailwindcss v4 installed successfully.');
    process.exit(0);
  }

  console.warn('[tailwind-repair] npm completed but tailwindcss v4 is still unavailable.');
  console.warn('[tailwind-repair] Please run manually: npm i -D tailwindcss@^4.2.1');
  process.exit(0);
} catch {
  console.warn('[tailwind-repair] Could not auto-install tailwindcss v4.');
  console.warn('[tailwind-repair] Run this command manually, then retry: npm i -D tailwindcss@^4.2.1');
  process.exit(0);
}
