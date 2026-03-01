import { existsSync } from 'node:fs';
import { execSync } from 'node:child_process';
import os from 'node:os';
import process from 'node:process';

const platformMap = { win32: 'win32', linux: 'linux', darwin: 'darwin' };
const archMap = { x64: 'x64', arm64: 'arm64' };

const platform = platformMap[os.platform()];
const arch = archMap[os.arch()];

if (!platform || !arch) {
  console.log(`[rollup-repair] Unsupported platform (${os.platform()}) or arch (${os.arch()}); skipping.`);
  process.exit(0);
}

const packageName = `@rollup/rollup-${platform}-${arch}`;
const modulePath = `node_modules/${packageName}`;

if (existsSync(modulePath)) {
  console.log(`[rollup-repair] ${packageName} already present.`);
  process.exit(0);
}

console.log(`[rollup-repair] ${packageName} is missing. Attempting optional install...`);

try {
  execSync(`npm i -E --no-audit --no-fund --save-optional ${packageName}`, {
    stdio: 'inherit',
    timeout: 20_000,
  });
  console.log(`[rollup-repair] Installed ${packageName}.`);
} catch {
  console.warn(`[rollup-repair] Could not auto-install ${packageName}.`);
  console.warn(`[rollup-repair] Run this command manually, then retry: npm i -E --save-optional ${packageName}`);
  process.exit(0);
}
