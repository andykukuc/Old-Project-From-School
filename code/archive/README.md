# Archive — Original Master Copy

This directory contains the original Vagrant/Packer infrastructure as designed by the team.
Do NOT modify. Kept for reference only.

## Contents

- `vagrant-packer/` — Original provisioning scripts used to build the Ubuntu 18.04 VM via
  Packer (VirtualBox ISO) and Vagrant. This was the source of truth that the Docker setup
  was mirrored from.

## Migration Note

The Docker setup (`docker-compose.yml`, `docker/`) is the active replacement for this
Vagrant/Packer configuration. All functionality from `post_install_vagrant.sh` has been
translated into the Docker web and db services.

Archived: 2026-04-13
