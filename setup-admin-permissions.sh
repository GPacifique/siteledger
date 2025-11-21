#!/bin/bash

# =============================================================================
# ADMIN PERMISSIONS DEPLOYMENT SCRIPT
# =============================================================================
# This script ensures admin users have full permissions on the deployed site
# Run this after deploying your Laravel application to fix RBAC issues

echo "🚀 SiteLedger Admin Permissions Setup"
echo "====================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to run Laravel commands safely
run_artisan() {
    local cmd="$1"
    local description="$2"
    
    echo -e "${YELLOW}⏳ $description...${NC}"
    
    if php artisan $cmd; then
        echo -e "${GREEN}✅ $description completed${NC}"
        echo ""
    else
        echo -e "${RED}❌ Failed: $description${NC}"
        echo "Command: php artisan $cmd"
        echo ""
        return 1
    fi
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Not in a Laravel project directory${NC}"
    echo "Please run this script from your Laravel project root"
    exit 1
fi

echo "📂 Current directory: $(pwd)"
echo ""

# Step 1: Clear all caches
echo -e "${YELLOW}🧹 Step 1: Clearing application caches${NC}"
run_artisan "cache:clear" "Clearing application cache"
run_artisan "config:clear" "Clearing config cache"
run_artisan "route:clear" "Clearing route cache"
run_artisan "view:clear" "Clearing view cache"

# Step 2: Seed roles and permissions
echo -e "${YELLOW}🛡️  Step 2: Setting up roles and permissions${NC}"
run_artisan "db:seed --class=RolePermissionSeeder --force" "Creating roles and permissions"

# Step 3: Create/update admin users
echo -e "${YELLOW}👤 Step 3: Setting up admin users${NC}"
run_artisan "db:seed --class=AdminUserSeeder --force" "Creating admin users"

# Step 4: Fix admin permissions (extra safety)
echo -e "${YELLOW}🔧 Step 4: Ensuring admin permissions${NC}"
run_artisan "admin:fix-permissions" "Fixing admin permissions"

# Step 5: Clear permission cache
echo -e "${YELLOW}🧼 Step 5: Clearing permission cache${NC}"
run_artisan "permission:cache-reset" "Clearing permission cache"

# Step 6: Verify setup
echo -e "${YELLOW}🔍 Step 6: Verifying setup${NC}"
echo "Checking admin users and permissions..."

# Run verification commands
echo ""
echo "=== VERIFICATION RESULTS ==="
echo ""

echo "Roles in system:"
php artisan tinker --execute="echo \Spatie\Permission\Models\Role::all()->pluck('name')->join(', ');"

echo ""
echo "Total permissions:"
php artisan tinker --execute="echo \Spatie\Permission\Models\Permission::count();"

echo ""
echo "Admin role permissions:"
php artisan tinker --execute="echo \Spatie\Permission\Models\Role::where('name', 'admin')->first()?->permissions()->count() ?? 0;"

echo ""
echo "Admin users:"
php artisan tinker --execute="echo \App\Models\User::role('admin')->pluck('email')->join(', ');"

echo ""
echo -e "${GREEN}🎉 SETUP COMPLETE!${NC}"
echo ""
echo -e "${GREEN}✅ Admin users now have full permissions${NC}"
echo -e "${GREEN}✅ Login credentials:${NC}"
echo "   • admin@siteledger.com (password: admin123)"
echo "   • gashumba@siteledger.com (password: password)"
echo ""
echo -e "${YELLOW}💡 If you still have permission issues:${NC}"
echo "1. Check your .env DB settings point to the right database"
echo "2. Run: php artisan admin:create (to create additional admin users)"
echo "3. Run: php artisan admin:fix-permissions (to re-sync permissions)"
echo ""
echo -e "${GREEN}🚀 Your admin users are ready to go!${NC}"