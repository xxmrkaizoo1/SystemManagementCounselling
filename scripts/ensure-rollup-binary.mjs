import { existsSync } from 'node:fs';
import { execSync } from 'node:child_process';
import os from 'node:os';
import process from 'node:process';

const platform = os.platform();
const arch = os.arch();

const isMusl = () => {
  if (platform !== 'linux') {
    return false;
  }

  if (typeof process.report?.getReport !== 'function') {
    return false;
  }

  const report = process.report.getReport();
  return !report.header?.glibcVersionRuntime;
};

const buildCandidates = () => {
  if (platform === 'win32') {
    if (arch === 'x64') return ['@rollup/rollup-win32-x64-msvc', '@rollup/rollup-win32-x64-gnu'];
    if (arch === 'arm64') return ['@rollup/rollup-win32-arm64-msvc'];
    if (arch === 'ia32') return ['@rollup/rollup-win32-ia32-msvc'];
    return [];
  }

  if (platform === 'darwin') {
    if (arch === 'x64') return ['@rollup/rollup-darwin-x64'];
    if (arch === 'arm64') return ['@rollup/rollup-darwin-arm64'];
    return [];
  }

  if (platform === 'linux') {
    if (arch === 'x64') {
      return isMusl()
        ? ['@rollup/rollup-linux-x64-musl', '@rollup/rollup-linux-x64-gnu']
        : ['@rollup/rollup-linux-x64-gnu', '@rollup/rollup-linux-x64-musl'];
    }

    if (arch === 'arm64') {
      return isMusl()
        ? ['@rollup/rollup-linux-arm64-musl', '@rollup/rollup-linux-arm64-gnu']
        : ['@rollup/rollup-linux-arm64-gnu', '@rollup/rollup-linux-arm64-musl'];
    }

    if (arch === 'arm') {
      return isMusl()
        ? ['@rollup/rollup-linux-arm-musleabihf', '@rollup/rollup-linux-arm-gnueabihf']
        : ['@rollup/rollup-linux-arm-gnueabihf', '@rollup/rollup-linux-arm-musleabihf'];
    }

    return [];
  }

  return [];
};

const candidates = buildCandidates();

if (candidates.length === 0) {
  console.log(`[rollup-repair] Unsupported platform (${platform}) or arch (${arch}); skipping.`);
  process.exit(0);
}

const hasAnyRollupBinary = () => candidates.some((packageName) => existsSync(`node_modules/${packageName}`));

if (hasAnyRollupBinary()) {
  console.log(`[rollup-repair] Rollup platform binary already present (${candidates.join(' or ')}).`);
  process.exit(0);
}

const packageName = candidates[0];
const installCommand = `npm i -E --no-audit --no-fund --save-optional ${packageName}`;

console.log(`[rollup-repair] Missing Rollup platform binary. Attempting optional install of ${packageName}...`);

try {
  execSync(installCommand, {
    stdio: 'inherit',
    timeout: 20_000,
  });

  if (hasAnyRollupBinary()) {
    console.log('[rollup-repair] Installed Rollup platform binary successfully.');
    process.exit(0);
  }

  console.warn('[rollup-repair] npm completed but no compatible Rollup platform binary is present.');
  console.warn(`[rollup-repair] Please install one manually (first choice): npm i -E --save-optional ${packageName}`);
  process.exit(0);
} catch {
  console.warn(`[rollup-repair] Could not auto-install ${packageName}.`);
  console.warn(`[rollup-repair] Run this command manually, then retry: npm i -E --save-optional ${packageName}`);
  process.exit(0);
}
