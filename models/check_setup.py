import pkg_resources
import sys

required_packages = {
    'opencv-python': '4.5.0',
    'mediapipe': '0.10.0',
    'numpy': '1.19.0'
}

def check_packages():
    for package, version in required_packages.items():
        try:
            dist = pkg_resources.get_distribution(package)
            print(f'{package} ({dist.version}) is installed')
        except pkg_resources.VersionConflict:
            print(f'Error: {package} version conflict')
        except pkg_resources.DistributionNotFound:
            print(f'Error: {package} is not installed')

if __name__ == "__main__":
    print("Checking setup...")
    check_packages()
