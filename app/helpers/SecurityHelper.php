<?php
/**
 * Security Helper Class
 * Provides security utilities for input validation and sanitization
 */

class SecurityHelper {
    
    /**
     * Validate product name
     */
    public static function validateProductName($name) {
        $name = trim($name);
        if (empty($name) || strlen($name) > 255) {
            return ['valid' => false, 'error' => 'Product name must be between 1 and 255 characters'];
        }
        return ['valid' => true];
    }

    /**
     * Validate quantity
     */
    public static function validateQuantity($quantity) {
        $quantity = intval($quantity);
        if ($quantity < 0 || $quantity > 999999) {
            return ['valid' => false, 'error' => 'Quantity must be between 0 and 999999'];
        }
        return ['valid' => true];
    }

    /**
     * Validate price
     */
    public static function validatePrice($price) {
        $price = floatval($price);
        if ($price < 0 || $price > 999999.99) {
            return ['valid' => false, 'error' => 'Price must be between 0 and 999999.99'];
        }
        return ['valid' => true];
    }

    /**
     * Validate category ID
     */
    public static function validateCategoryId($categoryId) {
        $categoryId = intval($categoryId);
        if ($categoryId <= 0) {
            return ['valid' => false, 'error' => 'Invalid category ID'];
        }
        return ['valid' => true];
    }

    /**
     * Sanitize input string
     */
    public static function sanitizeString($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     */
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'Invalid email format'];
        }
        return ['valid' => true];
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Rate limiting check
     */
    public static function checkRateLimit($key, $limit = 10, $window = 60) {
        $now = time();
        $cacheKey = "ratelimit_" . md5($key);
        $cache = $_SESSION[$cacheKey] ?? [];
        
        // Clean old entries
        $cache = array_filter($cache, function($timestamp) use ($now, $window) {
            return $now - $timestamp < $window;
        });
        
        if (count($cache) >= $limit) {
            return false;
        }
        
        $cache[] = $now;
        $_SESSION[$cacheKey] = $cache;
        return true;
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent($event, $details = []) {
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/security_' . date('Y-m-d') . '.log';
        $logEntry = sprintf(
            "[%s] %s | IP: %s | User: %s | Details: %s\n",
            date('Y-m-d H:i:s'),
            $event,
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            $_SESSION['username'] ?? 'Anonymous',
            json_encode($details)
        );

        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}
?>
